package cl.blackbird.reino.network;

import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.widget.Toast;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import cl.blackbird.reino.JoinActivity;

/**
 * Created by carlos on 24/7/2014.
 */
public class RemoveAsyncSender extends AsyncTask<InitialDataMessage, Void, Boolean> {

    private final String baseRoute;
    private final Context context;

    public RemoveAsyncSender(Context context, String baseRoute){
        this.baseRoute = baseRoute;
        this.context = context.getApplicationContext();
    }
    @Override
    protected Boolean doInBackground(InitialDataMessage... params) {
        for(InitialDataMessage message : params){
            String url = message.getRoute();
            try {
                HttpClient client = new DefaultHttpClient();
                HttpGet getData = new HttpGet(url);
                HttpResponse responseGet = client.execute(getData);
                int responseCode = responseGet.getStatusLine().getStatusCode();
                HttpEntity resEntityGet = responseGet.getEntity();
                if(responseCode == 404){
                    return false;
                }else {
                    return true;
                }
            } catch (Exception e) {
                e.printStackTrace();
            }

            return false;
        }
        return null;
    }
    protected void onPostExecute(Boolean result) {
        super.onPostExecute(result);
        if(result){
            Intent intent = new Intent(context, JoinActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            context.startActivity(intent);
        }else{
            Toast.makeText(this.context, "Ha ocurrido un error \n Intente nuevamente", Toast.LENGTH_SHORT).show();
        }
    }
}