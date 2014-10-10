package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.FragmentTransaction;
import android.app.ListFragment;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import cl.blackbird.reino.Config;
import cl.blackbird.reino.R;
import cl.blackbird.reino.ReinoApplication;
import cl.blackbird.reino.fragment.ChangeClassFragment;
import cl.blackbird.reino.fragment.ListItemFragment;
import cl.blackbird.reino.fragment.LobbyFragment;
import cl.blackbird.reino.fragment.StoreFragment;
import cl.blackbird.reino.model.Item;
import cl.blackbird.reino.model.Player;

/**
 * Created by niko on 14/09/2014.
 */
public class StoreActivity extends Activity implements StoreFragment.StoreListener,ListItemFragment.itemListener,ChangeClassFragment.changeListener{
    private static final String TAG = "RASTORE";
    private Player player;
    private StoreFragment storeFragment;
    private ChangeClassFragment changeClassFragment;
    @Override
    protected void onResume() {
        super.onResume();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.frame_layout);
        if (savedInstanceState == null){
            player = (Player) getIntent().getExtras().getSerializable("player");
            storeFragment = StoreFragment.newInstance(player);
            getFragmentManager().beginTransaction()
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                    .add(R.id.container, storeFragment, StoreFragment.TAG)
                    .commit();
        }
    }

    @Override
    protected void onPause() {
        super.onPause();
    }
    @Override
    public void onBackPressed() {
        new AlertDialog.Builder(this)
                .setTitle("Retroceder")
                .setMessage("¿Seguro quieres volver?")
                .setNegativeButton(android.R.string.no, null)
                .setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        StoreActivity.super.onBackPressed();
                    }
                }).create().show();
    }

    @Override
    public void onChangeClass() {
        changeClassFragment = changeClassFragment.newInstance(player.characterType);
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container,changeClassFragment, ChangeClassFragment.TAG)
                .commit();
    }

    @Override
    public void onFinish() {
        Intent lobby = new Intent(this,LobbyActivity.class);
        lobby.putExtra("player",player);
        lobby.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(lobby);
        finish();
    }

    @Override
    public void onItemList(final int kind,final int type) {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("api/v1/item/list")
                .appendQueryParameter("kind",String.valueOf(kind)).appendQueryParameter("type",String.valueOf(type))
                .appendQueryParameter("player",androidId).build().toString();
        Log.d("url",url);
        JsonArrayRequest request = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        startItemList(response);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(getApplicationContext(),R.string.server_error,Toast.LENGTH_SHORT).show();
                    }
                });
        ReinoApplication.getInstance().getRequestQueue().add(request);

    }
    public void startItemList(JSONArray json){
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container,ListItemFragment.newInstance(json), ListItemFragment.TAG)
                .commit();
    }
    @Override
    public void onItemClick(final int id_item,final int precio) {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("/api/v1/item/buy").build().toString();
        StringRequest request = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(
                                getApplicationContext(),
                                "Compra exitosa",
                                Toast.LENGTH_LONG).show();
                        success(precio);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(
                                getApplicationContext(),
                                "Error al comprar",
                                Toast.LENGTH_LONG).show();
                    }
                }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("android_id",androidId);
                params.put("item_id", String.valueOf(id_item));
                return params;
            }
        };
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }
    public void success(int precio){
        player.credits= player.credits-precio;
        Intent lobby = new Intent(this, LobbyActivity.class);
        lobby.putExtra("player", player);
        startActivity(lobby);
        finish();
    }

    @Override
    public void onChangeClick(final int clase,final int precio) {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("/api/v1/player/change-type").build().toString();
        StringRequest request = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(
                                getApplicationContext(),
                                "Cambio de clase exitoso",
                                Toast.LENGTH_LONG).show();
                        success(precio);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(
                                getApplicationContext(),
                                "Error al cambiar",
                                Toast.LENGTH_LONG).show();
                    }
                }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("android_id",androidId);
                params.put("type_id", String.valueOf(clase));
                return params;
            }
        };
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }
}
