package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.BaseAdapter;
import android.widget.ListAdapter;
import android.widget.ListView;

import org.json.JSONArray;
import org.json.JSONException;

import java.util.ArrayList;
import java.util.List;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.Item;

public class ItemListFragment extends android.app.ListFragment {

    public static final String TAG = "RAFLISTITEM";
    private static final String ITEM_STRING ="ITEMS";
    private itemListener iListener;
    ItemAdapter adapter;

    public ItemListFragment(){

    }

    public static ItemListFragment newInstance(JSONArray itemsArray){
        ItemListFragment lif = new ItemListFragment();
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
                Log.e(TAG, "Error reading items");
            }
        }

    }
    private void buildAdapter(JSONArray itemList)throws  JSONException{
        List<Item> listItem = new ArrayList<Item>();
        for (int i = 0; i < itemList.length(); i++) {
            Item newItem = Item.fromJSON(itemList.getJSONObject(i));
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
        Item item=(Item)getListView().getItemAtPosition(position);
        Log.d(String.valueOf(item.id),String.valueOf(item.bought));
        if(iListener == null) return;
        if(item.bought==0) askToBuy(item);
        else askToEquip(item);
    }

    public void askToBuy(final Item item){
        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
        builder.setMessage(getString(R.string.ask_buy, item.name, item.price));
        builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                iListener.onBuyItem(item, (BaseAdapter) getListAdapter());
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

    public void askToEquip(final Item item){
            AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
            if(item.equipped==0) {
                builder.setMessage(getString(R.string.ask_equip, item.name));
            } else {
                builder.setMessage(getString(R.string.ask_unequip, item.name));
            }
            builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    iListener.onEquipItem(item, (BaseAdapter) getListAdapter());
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

    @Override
    public void setListAdapter(ListAdapter adapter) {
        super.setListAdapter(adapter);
    }

    public interface itemListener{
        public void onBuyItem(Item item, BaseAdapter adapter);
        public void onEquipItem(Item item, BaseAdapter adapter);
        public void onReturn();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if(item.getItemId() == android.R.id.home){
            iListener.onReturn();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}
