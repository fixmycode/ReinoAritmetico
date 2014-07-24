package cl.blackbird.reino.network;

/**
 * Created by carlos on 23/7/2014.
 */
public class InitialDataMessage {
    private String route, content;

    public InitialDataMessage(String route, String content){
        this.route = route;
        this.content = content;
    }

    public String getRoute(){
        return route;
    }

    public String getContent(){
        return content;
    }
}