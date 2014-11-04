package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
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
    private int id_item;
    private itemListener iListener;
    private Item newItem;
    private List<Item> i = new ArrayList<Item>();
    ItemAdapter adapter;
    int precio;

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
            newItem = Item.fromJSON(itemList.getJSONObject(i));
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
            builder.setMessage(getString(R.string.ask_buy, i.nombre, precio));
            builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    buyItem(id_item,precio);
                    dialog.dismiss();
                    popUp();
                }
                public void popUp(){
                    AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
                    builder.setMessage(getString(R.string.ask_buy,"hola ", precio));
                    builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {

                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            equipItem(id_item);
                            dialog.dismiss();
                        }
                    });
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

        else{

            if(i.equipped==0) {
                AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
                builder.setMessage(getString(R.string.ask_equip, i.nombre, i.precio));
                builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        equipItem(id_item);
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
            else{
                AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
                builder.setMessage(getString(R.string.ask_unequip, i.nombre, i.precio));
                builder.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        equipItem(id_item);
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

    }

    public void buyItem(int item_id,int price){
        if (iListener != null){
            iListener.onItemClick(item_id, price);
        }

    }
    public void equipItem(int item_id){
        if(iListener != null){
            iListener.onItemClick(item_id);
        }
    }
    @Override
    public void setListAdapter(ListAdapter adapter) {
        super.setListAdapter(adapter);
    }

    public interface itemListener{
        public void onItemClick(int item_id, int price);
        public void onItemClick(int item_id);
    }
}
