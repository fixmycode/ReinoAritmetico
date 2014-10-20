package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.content.Context;
import android.net.Uri;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.NetworkImageView;

import org.json.JSONArray;

import java.util.List;

import cl.blackbird.reino.Config;
import cl.blackbird.reino.R;
import cl.blackbird.reino.ReinoApplication;
import cl.blackbird.reino.model.Item;

/**
 * Created by niko on 08/10/2014.
 */
public class ItemAdapter extends BaseAdapter {
    Context context;
    List<Item> rowItem;

    ItemAdapter(Context context, List<Item> rowItem){
        this.context = context;
        this.rowItem= rowItem;
    }

    @Override
    public int getCount() {

        return rowItem.size();
    }

    @Override
    public Object getItem(int position) {

        return rowItem.get(position);
    }

    @Override
    public long getItemId(int position) {

        return rowItem.indexOf(getItem(position));
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        if (convertView == null) {
            LayoutInflater mInflater = (LayoutInflater) context
                    .getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.item, null);
        }



        NetworkImageView imgIcon = (NetworkImageView) convertView.findViewById(R.id.imageView);
        TextView nombre = (TextView) convertView.findViewById(R.id.name);
        TextView estado = (TextView) convertView.findViewById(R.id.state);
        TextView desc = (TextView) convertView.findViewById(R.id.descripcion);
        Item row_pos = rowItem.get(position);
        imgIcon.setImageUrl(getImageUrl(row_pos), ReinoApplication.getInstance().getImageLoader());
        nombre.setText(row_pos.nombre);
        desc.setText(row_pos.descripcion);

        if(row_pos.comprado==0) {
            estado.setText(String.valueOf("$ "+row_pos.precio));
        }
        else{
            estado.setText("Comprado");
        }

        return convertView;

    }

    private String getImageUrl(Item item) {
        return Uri.parse(Config.getServer(context)).buildUpon()
                .path(item.image)
                .build()
                .toString();
    }
}
