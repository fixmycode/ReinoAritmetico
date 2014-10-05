package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.net.Uri;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListAdapter;
import android.widget.ListView;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
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
import cl.blackbird.reino.model.Item;
import cl.blackbird.reino.model.School;

public class ListItemFragment extends android.app.ListFragment {

    public static final String TAG = "RAFLISTITEM";
    private static final String ITEM_STRING ="ITEMS";
    private int id_item;
    private itemListener iListener;

    public ListItemFragment(){

    }
    public static ListItemFragment newInstance(JSONArray itemsArray){
        ListItemFragment lif = new ListItemFragment();
        Bundle args = new Bundle();
        args.putString(ITEM_STRING,itemsArray.toString());
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

        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
        builder.setMessage(R.string.erase_confirmation);
        builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                buyItem(id_item);
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
        //super.onListItemClick(l, v, position, id);
    }

    public void buyItem(int id_item){
        final String androidId = Settings.Secure.getString(
                getActivity().getContentResolver(),
                Settings.Secure.ANDROID_ID);
        if (iListener != null){
            iListener.onItemClick(id_item, androidId);
        }

    }
    @Override
    public void setListAdapter(ListAdapter adapter) {
        super.setListAdapter(adapter);
    }

    public interface itemListener{

        public void onItemClick(int id_item,String android_ID);
    }
}
