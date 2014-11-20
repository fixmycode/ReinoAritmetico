package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentTransaction;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.MenuItem;
import android.widget.BaseAdapter;
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
import cl.blackbird.reino.fragment.ChangeTypeFragment;
import cl.blackbird.reino.fragment.ItemListFragment;
import cl.blackbird.reino.fragment.LoadingFragment;
import cl.blackbird.reino.fragment.StoreFragment;
import cl.blackbird.reino.model.Item;
import cl.blackbird.reino.model.Player;

public class StoreActivity extends Activity implements
        StoreFragment.StoreListener,
        ItemListFragment.itemListener,
        ChangeTypeFragment.ChangeListener {
    private static final String TAG = "RASTORE";
    private Player player;

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
            getFragmentManager().beginTransaction()
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                    .add(R.id.container, StoreFragment.newInstance(player), StoreFragment.TAG)
                    .commit();
        }
    }

    @Override
    public void onChangeClass() {
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, ChangeTypeFragment.newInstance(player.characterType), ChangeTypeFragment.TAG)
                .commit();
    }

    @Override
    public void onItemList(final int kind,final int type) {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("api/v1/item/list")
                .appendQueryParameter("kind",String.valueOf(kind)).appendQueryParameter("type",String.valueOf(type))
                .appendQueryParameter("player",androidId).build().toString();
        LoadingFragment.setLoadingMessage(this, R.string.getting_items);
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
                        Toast.makeText(getApplicationContext(),R.string.item_list_error,Toast.LENGTH_SHORT).show();
                        onReturn();
                    }
                });
        ReinoApplication.getInstance().getRequestQueue().add(request);

    }
    public void startItemList(JSONArray json){
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, ItemListFragment.newInstance(json), ItemListFragment.TAG)
                .commit();
    }
    @Override
    public void onBuyItem(final Item item, final BaseAdapter adapter) {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("/api/v1/item/buy").build().toString();
        StringRequest request = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        itemBought(item);
                        adapter.notifyDataSetChanged();
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(
                                getApplicationContext(),
                                R.string.buy_error,
                                Toast.LENGTH_LONG).show();
                    }
                }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("android_id",androidId);
                params.put("item_id", String.valueOf(item.id));
                return params;
            }
        };
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }

    @Override
    public void onEquipItem(final Item item, final BaseAdapter adapter) {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("/api/v1/item/equip").build().toString();
        StringRequest request = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        itemEquipped(item);
                        adapter.notifyDataSetChanged();
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(
                                getApplicationContext(),
                                R.string.equip_error,
                                Toast.LENGTH_LONG).show();
                    }
                }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("android_id",androidId);
                params.put("item_id", String.valueOf(item.id));
                return params;
            }
        };
        ReinoApplication.getInstance().getRequestQueue().add(request);

    }

    @Override
    public void onReturn() {
        Log.d(TAG, "on Return");
        getFragmentManager().beginTransaction()
                .replace(R.id.container, StoreFragment.newInstance(player))
                .commit();
        getFragmentManager().popBackStack();
    }

    public void itemBought(Item item){
        player.credits -= item.price;
        item.bought = 1;
        Toast.makeText(
            getApplicationContext(),
            R.string.buy_success,
            Toast.LENGTH_LONG).show();
        Fragment list = getFragmentManager().findFragmentById(R.id.container);
        if(list != null && list instanceof ItemListFragment){
            ((ItemListFragment) list).askToEquip(item);
        }
//        Intent result = new Intent();
//        result.putExtra("player", player);
//        setResult(Activity.RESULT_OK, result);
//        finish();
    }
    public void itemEquipped(Item item){
        item.equipped = item.equipped == 0 ? 1 : 0;
        Toast.makeText(
            getApplicationContext(),
            R.string.equip_success,
            Toast.LENGTH_LONG).show();
//        Intent result = new Intent();
//        result.putExtra("player", player);
//        setResult(Activity.RESULT_OK, result);
//        finish();
    }
    public void typeChanged(int price, int type){
        player.credits -= price;
        player.characterType = type;
        Intent result = new Intent();
        result.putExtra("player", player);
        setResult(Activity.RESULT_OK, result);
        finish();
    }

    @Override
    public void onChangeClick(final int type, final int price) {
        final String androidId = Settings.Secure.getString(
                getContentResolver(),
                Settings.Secure.ANDROID_ID);
        String url = Uri.parse(Config.getServer(this)).buildUpon().path("/api/v1/player/change-type").build().toString();
        Log.d("Clase ",String.valueOf(type));
        StringRequest request = new StringRequest(Request.Method.POST, url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Toast.makeText(
                                getApplicationContext(),
                                R.string.class_changed,
                                Toast.LENGTH_LONG).show();
                        typeChanged(price, type);
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        Toast.makeText(
                                getApplicationContext(),
                                R.string.change_error,
                                Toast.LENGTH_LONG).show();
                    }
                }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<String, String>();
                params.put("android_id",androidId);
                params.put("type_id", String.valueOf(type));
                return params;
            }
        };
        ReinoApplication.getInstance().getRequestQueue().add(request);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if(item.getItemId() == android.R.id.home){
            savePlayer();
            finish();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onBackPressed() {
        Log.d(TAG, "back press");
        savePlayer();
        super.onBackPressed();
    }

    private void savePlayer() {
        Intent data = new Intent();
        data.putExtra("player", player);
        setResult(Activity.RESULT_OK, data);
    }
}
