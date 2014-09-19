package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.FragmentTransaction;
import android.os.Bundle;

import cl.blackbird.reino.R;
import cl.blackbird.reino.fragment.LobbyFragment;
import cl.blackbird.reino.fragment.StoreFragment;
import cl.blackbird.reino.model.Player;

/**
 * Created by niko on 14/09/2014.
 */
public class StoreActivity extends Activity {

    private Player player;
    private StoreFragment storeFragment;
    @Override
    protected void onResume() {
        super.onResume();
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (savedInstanceState == null){
            player = (Player) getIntent().getExtras().getSerializable("player");
            storeFragment = StoreFragment.newInstance(player);
            getFragmentManager().beginTransaction()
                    .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                    .add(R.id.container, storeFragment, LobbyFragment.TAG)
                    .commit();
        }



        setContentView(R.layout.frame_layout);

    }

    @Override
    protected void onPause() {
        super.onPause();
    }
}
