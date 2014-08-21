package cl.blackbird.reino.model;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.Serializable;

/**
 * Created by Pablo Albornoz on 7/27/14.
 */
public class ClassRoom implements Serializable {
    public int id;
    public String name;

    public ClassRoom(int id, String name){
        this.id = id;
        this.name = name;
    }

    public static ClassRoom fromJSON(JSONObject json) throws JSONException {
        return new ClassRoom(json.getInt("id"), json.getString("name"));
    }

    @Override
    public String toString() {
        return this.name;
    }
}
