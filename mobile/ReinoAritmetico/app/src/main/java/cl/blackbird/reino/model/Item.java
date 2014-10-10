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
    public int id;
    public String nombre;
    public int precio;
    public String descripcion;
    public String image;
    public int tipo;
    public int clase;
    public int comprado;

    public Item(int id,String nombre,String descripcion,String image,int precio,int tipo,int clase,int comprado){
        this.id = id;
        this.precio = precio;
        this.nombre = nombre;
        this.image = image;
        this.descripcion = descripcion;
        this.tipo = tipo;
        this.clase = clase;
        this.comprado=comprado;

    }

    public static Item fromJSON(JSONObject jsonObject)throws JSONException{
        return new Item(jsonObject.getInt("id"),jsonObject.getString("nombre"),jsonObject.getString("description"),jsonObject.getString("image_path"),jsonObject.getInt("price"),jsonObject.getInt("item_type_id"),jsonObject.getInt("character_type_id"),jsonObject.getInt("comprado"));
    }

}
