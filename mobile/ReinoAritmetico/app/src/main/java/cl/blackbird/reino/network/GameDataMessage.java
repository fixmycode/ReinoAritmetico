package cl.blackbird.reino.network;

/**
 * Created by carlos on 26/7/2014.
 */
public class GameDataMessage {
    private String route, name, ANDROID_ID,address;

    public GameDataMessage(String route, String name, String ANDROID_ID, String address){
        this.route = route;
        this.name = name;
        this.ANDROID_ID = ANDROID_ID;
        this.address = address;
    }

    public String getRoute(){
        return route;
    }

    public String getName(){
        return name;
    }

    public String getANDROID_ID(){
        return ANDROID_ID;
    }

    public String getAddress(){
        return address;
    }
}