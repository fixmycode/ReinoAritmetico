package cl.blackbird.reino.fragment;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListAdapter;
import android.widget.ListView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.Item;
import cl.blackbird.reino.model.School;

public class ListItemFragment extends android.app.ListFragment {

    public static final String TAG = "RAFLISTITEM";
    private static final String ITEM_STRING ="ITEMS";

    private String name;
    private String creditos;
    private int id_item;

    public ListItemFragment(){

    }
    public ListItemFragment newInstance(JSONArray items){
        ListItemFragment lif = new ListItemFragment();
        Bundle args = new Bundle();
        args.putString(ITEM_STRING,items.toString());
        lif.setArguments(args);
        return  lif;
    }



    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.itemlist_layout,container,false);


        if(getArguments()!=null){
            try {
                JSONArray items = new JSONArray(getArguments().getString(ITEM_STRING));
                buildAdapter(items);
            }
            catch(JSONException e) {
                Log.e(TAG, "Error reading items");;
            }
        }
        return v;
    }
    private void buildAdapter(JSONArray itemList) throws JSONException{

        List<Item> listItem = new ArrayList<Item>();

        for(int i =0; i< itemList.length();i++){
            listItem.add(Item.fromJSONObject(itemList.getJSONObject(i)));
        }

        ArrayAdapter<Item> itemAdapter = new ArrayAdapter<Item>(
            getActivity(), android.R.layout.simple_list_item_1, listItem);
        setListAdapter(itemAdapter);


    }

    @Override
    public void onListItemClick(ListView l, View v, int position, long id) {
        Item i=(Item)getListView().getItemAtPosition(position);
        name = i.descripcion;
        creditos = i.precio;
        id_item = i.id;

        super.onListItemClick(l, v, position, id);
    }

    @Override
    public void setListAdapter(ListAdapter adapter) {
        super.setListAdapter(adapter);
    }
}
