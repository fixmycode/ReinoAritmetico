package cl.blackbird.reino.activity;

import android.app.Activity;
import android.app.FragmentTransaction;
import android.app.ListFragment;
import android.content.Intent;
import android.os.Bundle;

import java.util.List;

import cl.blackbird.reino.R;
import cl.blackbird.reino.fragment.ChangeClassFragment;
import cl.blackbird.reino.fragment.ListItemFragment;
import cl.blackbird.reino.fragment.LobbyFragment;
import cl.blackbird.reino.fragment.StoreFragment;
import cl.blackbird.reino.model.Player;

/**
 * Created by niko on 14/09/2014.
 */
public class StoreActivity extends Activity implements StoreFragment.StoreListener{

    private Player player;
    private StoreFragment storeFragment;
    private ChangeClassFragment changeClassFragment;
    private ListItemFragment listItemFragment;
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
                    .add(R.id.container, storeFragment, StoreFragment.TAG)
                    .commit();
        }



        setContentView(R.layout.frame_layout);

    }

    @Override
    protected void onPause() {
        super.onPause();
    }

    @Override
    public void onChangeClass() {
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container,changeClassFragment.newInstance(player.characterType), ChangeClassFragment.TAG)
                .commit();
    }

    @Override
    public void onFinish() {

    }

    @Override
    public void onItemList() {
        getFragmentManager().beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container,listItemFragment, ListItemFragment.TAG)
                .commit();

    }
}
