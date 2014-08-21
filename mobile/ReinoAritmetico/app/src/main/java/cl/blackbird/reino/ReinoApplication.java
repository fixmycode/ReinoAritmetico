package cl.blackbird.reino;

import android.app.Application;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.Volley;

public class ReinoApplication extends Application {

    private static ReinoApplication singleton;
    private RequestQueue requestQueue;

    @Override
    public void onCreate() {
        super.onCreate();
        requestQueue = Volley.newRequestQueue(this);
        singleton = this;
    }

    public synchronized static ReinoApplication getInstance(){
        return singleton;
    }

    public RequestQueue getRequestQueue(){
        return requestQueue;
    }

}
