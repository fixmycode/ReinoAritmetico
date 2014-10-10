package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.FragmentTransaction;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.support.v4.view.ViewPager;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;

import java.util.HashMap;
import java.util.Map;

import cl.blackbird.reino.Config;
import cl.blackbird.reino.R;
import cl.blackbird.reino.ReinoApplication;
import cl.blackbird.reino.fragment.LoadingFragment;
import cl.blackbird.reino.fragment.RegisterFragment;
import cl.blackbird.reino.model.Player;

/**
 * This activity takes care of the user registry. This is a result activity, and generates a result
 * when it's finish. Main Activity handles this result.
 * REMINDER: Activities don't interact with the UI, only the fragments can interact with the UI.
 */
public class RegisterActivity extends Activity implements RegisterFragment.RegisterListener {

    private long back_pressed;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.frame_layout);
        getClients();

    }

    /**
     * If the device is not registered, load the client list to start the registration process.
     */
    private void getClients(){
        LoadingFragment.setLoadingMessage(this, R.string.preparing_clients);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("api/v1/client/clients").build().toString();
        JsonArrayRequest request = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        startRegister(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        activityError(R.string.server_error);
                    }
                });
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }

    /**
     * Calls the registration form
     * @param json the client list
     */
    private void startRegister(JSONArray json){
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, RegisterFragment.newInstance(json), RegisterFragment.TAG)
                .commit();
        if (getActionBar() != null) {
            getActionBar().setTitle(R.string.register_and_play);
        }
    }

    /**
     * Tries to save the player data to the server
     * @param player the Player instance
     */
    private void tryToRegister(final Player player) {
        LoadingFragment.setLoadingMessage(this, R.string.saving_player);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("api/v1/player/register").build().toString();
        StringRequest request = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        String firstName = player.name.split("\\s")[0];
                        Toast.makeText(
                                getApplicationContext(),
                                String.format(getString(R.string.welcome_player), firstName),
                                Toast.LENGTH_LONG).show();
                        activitySuccess(player);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(
                                getApplicationContext(),
                                R.string.register_error,
                                Toast.LENGTH_LONG).show();
                        getClients();
                    }
                }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("android_id", player.androidID);
                params.put("name", player.name);
                params.put("character_type", String.valueOf(player.characterType));
                params.put("school", String.valueOf(player.school.id));
                params.put("classroom", String.valueOf(player.classRoom.id));
                return params;
            }
        };
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }

    /**
     * The user can exit the registry process if he presses the back button twice within 2 seconds.
     */
    @Override
    public void onBackPressed() {
        if (back_pressed + 2000 > System.currentTimeMillis()){
            activityCanceled();
        }
        else Toast.makeText(getBaseContext(), R.string.press_twice, Toast.LENGTH_SHORT).show();
        back_pressed = System.currentTimeMillis();
    }

    /**
     * The event consumed from the form fragment when registration is completed.
     * @param player the player data
     */
    @Override
    public void onRegisterPlayer(Player player) {
        tryToRegister(player);
    }

    /**
     * The event consumed from the form fragment if there's any error with the data.
     * @param message the error message
     */
    @Override
    public void onRegisterError(int message) {
        activityError(message);
    }

    /**
     * Sends back the player data to the Main Activity
     * @param player the player data
     */
    private void activitySuccess(Player player){
        Intent result = new Intent();
        result.putExtra("player", player);
        setResult(Activity.RESULT_OK, result);
        finish();
    }

    /**
     * Sends an error to the main activity if registration was unsuccessful.
     */
    private void activityError(int message){
        Intent result = new Intent();
        result.putExtra("error", true);
        setResult(Activity.RESULT_CANCELED, result);
        finish();
    }

    private void activityCanceled() {
        setResult(Activity.RESULT_CANCELED);
        finish();
    }
}
