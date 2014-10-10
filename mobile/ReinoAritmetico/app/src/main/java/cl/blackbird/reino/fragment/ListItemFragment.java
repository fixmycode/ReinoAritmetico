package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.media.Image;
import android.net.Uri;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import cl.blackbird.reino.Config;
import cl.blackbird.reino.R;
import cl.blackbird.reino.ReinoApplication;
import cl.blackbird.reino.activity.LobbyActivity;
import cl.blackbird.reino.model.Item;
import cl.blackbird.reino.model.School;

public class ListItemFragment extends android.app.ListFragment {

    public static final String TAG = "RAFLISTITEM";
    private static final String ITEM_STRING ="ITEMS";
    private int id_item;
    private itemListener iListener;
    private Item newItem;
    private List<Item> i = new ArrayList<Item>();
    ItemAdapter adapter;
    String[] nombre;
    int precio;

    public ListItemFragment(){

    }public static ListItemFragment newInstance(JSONArray itemsArray){
        ListItemFragment lif = new ListItemFragment();
        Bundle args = new Bundle();
        args.putString(ITEM_STRING,itemsArray.toString());
        lif.setArguments(args);
        return  lif;
    }
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if(getArguments()!=null){
            try {
                JSONArray items = new JSONArray(getArguments().getString(ITEM_STRING));
                buildAdapter(items);
            }
            catch(JSONException e) {
                Log.e(TAG, "Error reading items");;
            }
        }

    }
    private void buildAdapter(JSONArray itemList)throws  JSONException{
        List<Item> listItem = new ArrayList<Item>();
        for (int i = 0; i < itemList.length(); i++) {
            newItem = Item.fromJSONObject(itemList.getJSONObject(i));
            Log.d(newItem.nombre,String.valueOf(newItem.comprado));
            /*String url = Uri.parse(Config.getServer(getActivity())).buildUpon().path("api/v1/item/image")
                    .appendQueryParameter("id",String.valueOf(newItem.id)).build().toString();
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
                            Toast.makeText(getActivity().getApplicationContext(),R.string.server_error,Toast.LENGTH_SHORT).show();
                        }
                    });
            ReinoApplication.getInstance().getRequestQueue().add(request);*/
            listItem.add(newItem);
        }
        adapter = new ItemAdapter(getActivity().getApplicationContext(),listItem);
        setListAdapter(adapter);
    }
    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            iListener = (itemListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnFragmentInteractionListener");
        }
    }

    @Override
    public void onListItemClick(ListView l, View v, int position, long id) {
        Item i=(Item)getListView().getItemAtPosition(position);
        id_item = i.id;
        precio= i.precio;
        Log.d(String.valueOf(i.id),String.valueOf(i.comprado));

        if(i.comprado==0) {

            AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
            builder.setMessage("deseas comprar el objeto?");
            builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    buyItem(id_item,precio);
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

    }

    public void buyItem(int id_item,int precio){
        if (iListener != null){
            iListener.onItemClick(id_item, precio);
        }

    }
    @Override
    public void setListAdapter(ListAdapter adapter) {
        super.setListAdapter(adapter);
    }

    public interface itemListener{
        public void onItemClick(int id_item,int precio);
    }
}
