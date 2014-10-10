package cl.blackbird.reino.activity;

import android.app.ActionBar;
import android.app.Activity;
import android.app.FragmentTransaction;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.provider.Settings;
import android.util.Log;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;

import org.json.JSONException;
import org.json.JSONObject;

import android.os.Handler;

import cl.blackbird.reino.Config;
import cl.blackbird.reino.R;
import cl.blackbird.reino.ReinoApplication;
import cl.blackbird.reino.fragment.LoadingFragment;
import cl.blackbird.reino.fragment.SettingsFragment;
import cl.blackbird.reino.model.Player;

/**
 * This activity takes care of the first API calls and the main operations of the application.
 * It determines if the user needs to register or if he's allowed in the lobby.
 * REMINDER: Activities don't interact with the UI, only the fragments can interact with the UI.
 */
public class MainActivity extends Activity {
    private static final String TAG = "RA_MAIN";
    private static final int REGISTER_CODE = 1;
    private Handler handler = new Handler();
    private RetryRunnable retryConnection = new RetryRunnable();
    private int waitSec = 5;
    private Boolean registrationError = false;
    private boolean settingsOpen = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        PreferenceManager.setDefaultValues(this, R.xml.pref_general, false);
        setContentView(R.layout.frame_layout);
    }

    @Override
    protected void onResume() {
        super.onResume();
        waitSec = 5;
        checkDeviceStatus();
    }

    private RequestQueue getQueue() {
        return ReinoApplication.getInstance().getRequestQueue();
    }

    /**
     * Checks that the devices is registered in our system.
     * If the device is registered, the app proceeds to lobby.
     * Otherwise it calls RegisterActivity.
     */
    private void checkDeviceStatus() {
        LoadingFragment.setLoadingMessage(this, R.string.checking_status);
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon()
                .path("api/v1/player/identify")
                .appendQueryParameter("id", androidId)
                .build().toString();
        JsonObjectRequest request = new JsonObjectRequest(Request.Method.GET, url, null,
                new Response.Listener<JSONObject>() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            Player player = Player.fromJSON(response);
                            player.androidID = androidId;
                            String firstName = player.name.split("\\s")[0];
                            Toast.makeText(
                                    getApplicationContext(),
                                    String.format(getString(R.string.welcome_player), firstName),
                                    Toast.LENGTH_LONG).show();
                            startLobby(player);
                        } catch (JSONException e) {
                            e.printStackTrace();
                            Toast.makeText(
                                    getApplicationContext(),
                                    R.string.welcome_player_error,
                                    Toast.LENGTH_LONG).show();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Log.d(TAG, "Error identifying the device");

                        if(error.networkResponse != null && error.networkResponse.statusCode == 404 && !registrationError){
                            startRegistration();
                        } else {
                            Toast.makeText(
                                    getApplicationContext(),
                                    String.format(getString(R.string.server_error_retrying), waitSec),
                                    Toast.LENGTH_SHORT).show();
                            handler.postDelayed(retryConnection, waitSec * 1000);
                        }
                    }
                });
        request.setTag(TAG);
        getQueue().add(request);
    }


    private class RetryRunnable implements Runnable {
        @Override
        public void run() {
            checkDeviceStatus();
            if(waitSec < 60){
                waitSec = waitSec*2;
            }
        }
    }

    @Override
    protected void onPause() {
        super.onPause();
        getQueue().cancelAll(TAG);
        handler.removeCallbacks(retryConnection);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater inflater = getMenuInflater();
        inflater.inflate(R.menu.main, menu);
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case R.id.action_settings:
                openSettings();
                return true;
            case android.R.id.home:
                if(settingsOpen) {
                    ActionBar actionBar = getActionBar();
                    if (actionBar != null) {
                        getActionBar().setDisplayHomeAsUpEnabled(false);
                    }
                    waitSec = 5;
                    registrationError = false;
                    checkDeviceStatus();
                    settingsOpen = false;
                    return true;
                }
                return false;
            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private void openSettings() {
        handler.removeCallbacks(retryConnection);
        getQueue().cancelAll(TAG);
        ActionBar actionBar = getActionBar();
        if(actionBar != null) {
            getActionBar().setDisplayHomeAsUpEnabled(true);
        }
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, new SettingsFragment())
                .commit();
        settingsOpen = true;
    }



    /**
     * Starts the registration process while the main activity waits.
     */
    private void startRegistration(){
        Intent register = new Intent(this, RegisterActivity.class);
        startActivityForResult(register, REGISTER_CODE);
    }

    /**
     * Called when the main activity receives the result from another activity.
     * @param requestCode used to identify the other activity
     * @param resultCode used to identify the result
     * @param data intent that the other activity could have sent back
     */
    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if(requestCode == REGISTER_CODE){
            if(resultCode == Activity.RESULT_OK){
                Player player = (Player) data.getExtras().getSerializable("player");
                startLobby(player);
            } else {
                if(data != null){
                    registrationError = data.getExtras().getBoolean("error", false);
                }
                checkDeviceStatus();
            }
        }
    }

    private void startLobby(Player player) {
        Intent lobby = new Intent(this, LobbyActivity.class);
        lobby.putExtra("player", player);
        lobby.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(lobby);
        finish();
    }

}
