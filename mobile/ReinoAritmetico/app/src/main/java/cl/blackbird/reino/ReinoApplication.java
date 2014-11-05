package cl.blackbird.reino;

import android.app.Application;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.Volley;

import cl.blackbird.reino.util.LruBitmapCache;

public class ReinoApplication extends Application {

    private static ReinoApplication singleton;
    private RequestQueue requestQueue;
    private ImageLoader imageLoader;

    @Override
    public void onCreate() {
        super.onCreate();
        requestQueue = Volley.newRequestQueue(this);
        imageLoader = new ImageLoader(requestQueue, new LruBitmapCache());
        singleton = this;
    }

    public synchronized static ReinoApplication getInstance(){
        return singleton;
    }

    public RequestQueue getRequestQueue(){
        return requestQueue;
    }

    public ImageLoader getImageLoader(){
        return imageLoader;
    }

}
