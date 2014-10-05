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
public class StoreActivity extends Activity implements StoreFragment.StoreListener,ListItemFragment.itemListener{

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
        if (savedInstanceState == null){
            player = (Player) getIntent().getExtras().getSerializable("player");
            storeFragment = StoreFragment.newInstance(player);
            getFragmentManager().beginTransaction()
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                    .add(R.id.container, storeFragment, StoreFragment.TAG)
                    .commit();
        }



        setContentView(R.layout.frame_layout);

    }

    @Override
    protected void onPause() {
        super.onPause();
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

    }

    @Override
    public void onItemList() {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("item")
                .appendQueryParameter("id", androidId).build().toString();
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
    public void onItemClick(final int id_item,final String androidID) {
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("buy").build().toString();
        StringRequest request = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(
                                getApplicationContext(),
                                "Compra exitosa",
                                Toast.LENGTH_LONG).show();
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
                params.put("uid",androidID);
                params.put("id", Integer.toString(id_item));
                return params;
            }
        };
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }

}
