package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.FragmentTransaction;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import cl.blackbird.reino.Config;
import cl.blackbird.reino.R;
import cl.blackbird.reino.ReinoApplication;
import cl.blackbird.reino.fragment.LobbyFragment;
import cl.blackbird.reino.model.Player;

/**
 * This activity takes care of the Lobby API Calls.
 * REMINDER: Activities don't interact with the UI, only the fragments can interact with the UI.
 */
public class LobbyActivity extends Activity implements LobbyFragment.LobbyListener {
    private static final String TAG = "RALOBBY";
    private static final int GAME_CODE = 2;
    private Player player;
    private String server;
    private HashMap<String, String> routingTable;
    private LobbyFragment lobbyFragment;
    private long back_pressed;

    /**
     * Loads the fragment in the activity. Because the activity doesn't require previous network
     * operations, we can load the Lobby Fragment directly.
     * @param savedInstanceState this is a Bundle containing data from memory.
     */
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.frame_layout);
        if (savedInstanceState == null){
            player = (Player) getIntent().getExtras().getSerializable("player");
            lobbyFragment = new LobbyFragment();
            getFragmentManager().beginTransaction()
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                    .add(R.id.container, lobbyFragment, LobbyFragment.TAG)
                    .commit();
        }
    }

    /**
     * On resume, we recreate the cache map, this is because we don't want to store addresses that
     * can be stale in a few minutes.
     */
    @Override
    protected void onResume() {
        super.onResume();
        routingTable = new HashMap<String, String>();
    }

    /**
     * We allow the user to exit the application by pressing the back button two times within
     * two seconds. There's nothing to go back to.
     */
    @Override
    public void onBackPressed() {
        if (back_pressed + 2000 > System.currentTimeMillis()) finish();
        else Toast.makeText(getBaseContext(), R.string.press_twice, Toast.LENGTH_SHORT).show();
        back_pressed = System.currentTimeMillis();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.lobby, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_delete) {
            askToEraseCharacter();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    /**
     * API call to get the game server address from the main server. The address is then cached
     * along with the code, this is for not calling the server every time we input the same code
     * @param code the game room code
     */
    @Override
    public void onJoinServer(final String code) {
        String cachedServer = routingTable.get(code);
        if(cachedServer != null){
            Log.d(TAG, "Using cached server: "+cachedServer);
            joinServer(player, cachedServer);
        } else {
            String url = Uri.parse(Config.getServer(this)).buildUpon()
                    .path("server")
                    .appendQueryParameter("uid", code)
                    .build().toString();
            JsonObjectRequest request = new JsonObjectRequest(Request.Method.GET, url, null,
                    new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {
                            try {
                                joinServer(player, response.getString("address"));
                                routingTable.put(code, response.getString("address"));
                            } catch (JSONException e) {
                                e.printStackTrace();
                                forceLeave();
                            }
                        }
                    },
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            error.printStackTrace();
                            forceLeave();
                        }
                    }
            );
            ReinoApplication.getInstance().getRequestQueue().add(request);
        }
    }


    /**
     * Calls the Game Activity with the server information we captured. This activity returns a
     * result that the lobby must expect.
     * @param player the player data
     * @param server the game server address
     */
    private void joinServer(final Player player, final String server) {
        Log.d(TAG, "Joining server: "+server);
        Intent intent = new Intent(this, GameActivity.class);
        intent.putExtra("player", player);
        intent.putExtra("server", server);
        startActivityForResult(intent, GAME_CODE);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        switch (resultCode) {
            case Activity.RESULT_OK:
                Log.d(TAG, "Activity ended OK");
                break;
            case Activity.RESULT_CANCELED:
                Log.d(TAG, "Activity canceled");
                forceLeave();
                break;
        }
    }

    /**
     * Sets the server property
     * @param server the game server address
     */
    private void setServer(String server) {
        this.server = server;
    }

    /**
     * In case of any errors on the API calls, tells the fragment to reset the UI to a disconnected
     * state.
     */
    private void forceLeave() {
        Toast.makeText(
                getApplicationContext(),
                R.string.join_error,
                Toast.LENGTH_LONG).show();
    }

    /**
     * Displays a dialog that lets you erase your character, for interal use.
     */
    private void askToEraseCharacter() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage(R.string.erase_confirmation);
        builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                eraseCharacter();
                dialog.dismiss();
            }
        });
        builder.setNegativeButton(R.string.no, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.cancel();
            }
        });
        AlertDialog dialog = builder.create();
        dialog.show();
    }

    private void eraseCharacter(){
        String url = Uri.parse(Config.getServer(this)).buildUpon()
                .path("delete")
                .appendQueryParameter("id", player.androidID)
                .build().toString();
        StringRequest request = new StringRequest(Request.Method.GET, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        finish();
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        //do nothing, don't even sweat it
                    }
                }
        );
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }
}
