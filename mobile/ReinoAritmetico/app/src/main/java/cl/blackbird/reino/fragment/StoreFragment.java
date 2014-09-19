package cl.blackbird.reino.fragment;

import android.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.Player;

/**
 * Created by niko on 14/09/2014.
 */
public class StoreFragment extends Fragment {
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View layout = inflater.inflate(R.layout.lobby_layout, container, false);
        

        return layout;
    }

    public StoreFragment() {
        //empty constructor
    }

    public static StoreFragment newInstance(Player player) {
        Bundle args = new Bundle();
        args.putSerializable("player", player);
        StoreFragment instance = new StoreFragment();
        instance.setArguments(args);
        return instance;
    }

}
