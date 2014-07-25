package cl.blackbird.reino;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.provider.Settings;
import android.view.View;

import cl.blackbird.reino.network.InitialDataMessage;
import cl.blackbird.reino.network.RemoveAsyncSender;


public class JoinActivity extends Activity {
	@Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_join);
    }
     
	public void join(View view){
		Intent i = new Intent(this,GameActivity.class);
		startActivity(i);
	}

	public void salir(View view){
        final String ANDROID_ID = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        InitialDataMessage message = new InitialDataMessage("http://ludus.noip.me//leave?id="+ANDROID_ID, "existe");
        RemoveAsyncSender sender = new RemoveAsyncSender(JoinActivity.this, message.getRoute().toString());
        sender.execute(message);
	}
}
