package cl.blackbird.reino;

import android.app.Activity;
import android.os.Bundle;
import android.provider.Settings;
import android.view.View;

import cl.blackbird.reino.network.GameAsyncSender;
import cl.blackbird.reino.network.GameDataMessage;
import cl.blackbird.reino.network.InitialDataMessage;
import cl.blackbird.reino.network.RemoveAsyncSender;

public class GameActivity extends Activity {
    String name, address;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_play);
        name = getIntent().getStringExtra("name");
        address = getIntent().getStringExtra("address");
			
	}

    public void salir(View view){
        final String ANDROID_ID = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        GameDataMessage message = new GameDataMessage(address+"/leave?android_id="+ANDROID_ID, name, ANDROID_ID, address);
        RemoveAsyncSender sender = new RemoveAsyncSender(GameActivity.this, message.getRoute().toString());
        sender.execute(message);
    }
	

}