package cl.blackbird.reino;

import android.app.Activity;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.provider.Settings.Secure;

import cl.blackbird.reino.network.InitialAsyncSender;
import cl.blackbird.reino.network.InitialDataMessage;

public class LoadActivity extends Activity {
	private final int WAIT_TIME=2500;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_load);
		findViewById(R.id.progressBar1).setVisibility(View.VISIBLE);
        final String ANDROID_ID = Secure.getString(getContentResolver(),Secure.ANDROID_ID);
		new Handler().postDelayed(new Runnable(){
			@Override
			public void run(){
                InitialDataMessage message = new InitialDataMessage("http://192.168.0.112/identify?id=<"+ANDROID_ID+">", "existe");

				try {
					Thread.sleep(1000);
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}

                InitialAsyncSender sender = new InitialAsyncSender(LoadActivity.this, message.getRoute().toString());
                sender.execute(message);

                System.out.println("ANDROID_ID = "+ANDROID_ID);
			}
		},WAIT_TIME);
	}
}
