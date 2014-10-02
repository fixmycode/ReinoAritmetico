package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ListView;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.Player;

/**
 * Created by niko on 14/09/2014.
 */
public class StoreFragment extends Fragment {

    public static final String TAG = "RAFSTORE";

    private Button helmetButton;
    private Button weaponButton;
    private Button classButton;
    private Button okButton;
    private StoreListener sListener;
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

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View layout = inflater.inflate(R.layout.store_layout, container, false);

        setButtons(layout);



        return layout;
    }


    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            sListener = (StoreListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement LobbyListener");
        }
    }

    /**
     * If we take out the fragment, destroy the listener
     */
    @Override
    public void onDetach() {
        super.onDetach();
        sListener = null;
    }

    public void setButtons(View layout){
        helmetButton=(Button) layout.findViewById(R.id.helmetButton);
        helmetButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sListener.onItemList();

            }
        });
        weaponButton=(Button) layout.findViewById(R.id.weaponButton);
        weaponButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sListener.onItemList();
            }
        });
        classButton=(Button) layout.findViewById(R.id.classButton);
        classButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sListener.onChangeClass();
            }
        });
        okButton=(Button) layout.findViewById(R.id.okButton);
        okButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sListener.onFinish();
            }
        });
    }

    public interface StoreListener{
        public void onItemList();
        public void onChangeClass();
        public void onFinish();
    }

}
