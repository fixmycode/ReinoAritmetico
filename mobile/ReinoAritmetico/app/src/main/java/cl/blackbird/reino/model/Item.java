package cl.blackbird.reino.model;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 * Created by niko on 14/09/2014.
 */
public class Item {
    public String androidID;
    public int id;
    public String precio;
    public String descripcion;
    public boolean tipo;
    public String clase;

    public Item(int id,String precio,String descripcion,boolean tipo,String clase){
        this.androidID = null;
        this.id = id;
        this.precio = precio;
        this.descripcion = descripcion;
        this.tipo = tipo;
        this.clase = clase;

    }
    public Item(int id,String precio,String descripcion,boolean tipo,String clase, String androidID){

        this.androidID = androidID;
        this.id = id;
        this.precio = precio;
        this.descripcion = descripcion;
        this.tipo = tipo;
        this.clase = clase;

    }

    public static Item fromJSONObject(JSONObject jsonObject)throws JSONException{
        return new Item(jsonObject.getInt("id"),jsonObject.getString("precio"),jsonObject.getString("descripcion"),jsonObject.getBoolean("tipo"),jsonObject.getString("clase"));
    }

}
