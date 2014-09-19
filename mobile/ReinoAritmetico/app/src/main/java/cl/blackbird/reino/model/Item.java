package cl.blackbird.reino.model;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by niko on 14/09/2014.
 */
public class Item {
    private int id;
    private String cost;

    public Item(int id,String cost){
        this.id = id;
        this.cost = cost;

    }
    public int getId() {
        return id;
    }

    public String getCost() {
        return cost;
    }

    public static Item fromJSONObject(JSONObject jsonObject)throws JSONException{
        return new Item(jsonObject.getInt("id"),jsonObject.getString("cost"));
    }

}
