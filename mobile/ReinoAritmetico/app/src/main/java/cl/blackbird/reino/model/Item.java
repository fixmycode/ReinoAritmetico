package cl.blackbird.reino.model;

import org.json.JSONException;
import org.json.JSONObject;

public class Item {
    public int id;
    public String name;
    public int price;
    public String description;
    public String image;
    public int type;
    public int playerType;
    public int bought;
    public int equipped;

    public Item(int id,String name,String description,String image,int price,int type,int playerType, int equipped,int bought){
        this.id = id;
        this.price = price;
        this.name = name;
        this.image = image;
        this.description = description;
        this.type = type;
        this.playerType = playerType;
        this.equipped = equipped;
        this.bought=bought;

    }

    public static Item fromJSON(JSONObject jsonObject)throws JSONException{
        return new Item(jsonObject.getInt("id"),jsonObject.getString("nombre"),jsonObject.getString("description")
                ,jsonObject.getString("image_path"),jsonObject.getInt("price")
                ,jsonObject.getInt("item_type_id"),jsonObject.getInt("character_type_id")
                ,jsonObject.getInt("equipped"),jsonObject.getInt("comprado"));
    }
}
