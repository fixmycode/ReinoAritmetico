package cl.blackbird.reino.network;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.HTTP;

import java.util.ArrayList;
import java.util.List;

import cl.blackbird.reino.GameActivity;

/**
 * Created by carlos on 26/7/2014.
 */
public class GameAsyncSender extends AsyncTask<GameDataMessage, Void, Boolean> {

    private final String baseRoute;
    private final Context context;
    String name, address;

    public GameAsyncSender(Context context, String baseRoute){
        this.baseRoute = baseRoute;
        this.context = context;
    }

    @Override
    protected Boolean doInBackground(GameDataMessage... params) {
        for(GameDataMessage message : params){
            name = message.getName();
            address = message.getAddress();
            String url = message.getRoute();
            HttpClient client = new DefaultHttpClient();
            HttpPost postData = new HttpPost(url);
            try {
                List<NameValuePair> urls = new ArrayList<NameValuePair>();
                urls.add(new BasicNameValuePair("name", message.getName()));
                urls.add(new BasicNameValuePair("android_id",message.getANDROID_ID()));
                postData.setEntity(new UrlEncodedFormEntity(urls, HTTP.UTF_8));
                HttpResponse response = client.execute(postData);
                int responseCode = response.getStatusLine().getStatusCode();
                if(responseCode == 200){
                    return true;
                } else {
                    Log.e("ASYNC", "Error "+responseCode+" al conectar con "+url);
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return false;
        }
        return null;
    }

    @Override
    protected void onPostExecute(Boolean result) {
        Intent intent;
        if(result){
            intent = new Intent(context, GameActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            intent.putExtra("name", name);
            intent.putExtra("address", address);
            context.startActivity(intent);
        }
        else {
            Toast.makeText(this.context, "No ha sido posible ingresar a la partida.", Toast.LENGTH_SHORT).show();
        }
    }
}