package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.content.Intent;
import android.content.res.TypedArray;
import android.graphics.drawable.AnimationDrawable;
import android.os.Bundle;
import android.app.Fragment;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import org.w3c.dom.Text;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.Player;

public class LobbyFragment extends Fragment implements View.OnClickListener {
    public static final String TAG = "RAFLOBBY";
    private LobbyListener mListener;
    private EditText lobbyCode;
    private ImageView characterImage;
    private Button joinButton;
    private Button shopButton;
    private Player player;
    private TextView nameText;
    private TextView characterTypeText;
    private TextView creditsText;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.lobby_layout, container, false);
        player = (Player) getArguments().getSerializable("player");
        characterImage = (ImageView) layout.findViewById(R.id.character_image);

        TypedArray drawableArray = getResources().obtainTypedArray(R.array.character_list);
        int characterId = drawableArray.getResourceId(player.characterType, -1);
        characterImage.setImageResource(characterId);
        lobbyCode = (EditText) layout.findViewById(R.id.lobby_code);
        joinButton = (Button) layout.findViewById(R.id.join_button);
        joinButton.setOnClickListener(this);

        nameText =(TextView) layout.findViewById(R.id.nameText);
        nameText.setText(player.name);

        characterTypeText = (TextView) layout.findViewById(R.id.characterTypeText);
        int characterType = 0;
        if(player.characterType==0){characterType = R.string.warrior;}
        else{
            if(player.characterType==1){characterType = R.string.wizard;}
            else{if(player.characterType == 2){characterType = R.string.archer;}}
        }

        characterTypeText.setText(characterType);

        creditsText =(TextView) layout.findViewById(R.id.creditsText);
        creditsText.setText("Creditos: $"+String.valueOf(player.credits));

        //shopButton=(Button) layout.findViewById(R.id.shopButton);
        /*shopButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mListener.onJoinShop();
                Log.d("hola","hola");
            }
        });*/



        checkValidForm(lobbyCode.getText(), joinButton);

        lobbyCode.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }

            @Override
            public void afterTextChanged(Editable s) {
                checkValidForm(s, joinButton);
            }
        });
        return layout;
    }

    public LobbyFragment() {
        //empty constructor
    }

    public static LobbyFragment newInstance(Player player) {
        Bundle args = new Bundle();
        args.putSerializable("player", player);
        LobbyFragment instance = new LobbyFragment();
        instance.setArguments(args);
        return instance;
    }

    /**
     * Checks if the field has any text and then enable the button
     * @param text the form field
     * @param button the button
     */
    private void checkValidForm(Editable text, Button button) {
        button.setEnabled(!text.toString().isEmpty());
    }

    /**
     * When we attach the fragment to an activity, we add it as a listener.
     * @param activity
     */
    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (LobbyListener) activity;
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
        mListener = null;
    }

    @Override
    public void onClick(View v) {
        String code = lobbyCode.getText().toString();
        mListener.onJoinServer(code);
    }

    /**
     * Interface for the activity to communicate with the fragment
     * Read about the Listener pattern!
     */
    public interface LobbyListener {
        public void onJoinServer(String code);
        //public void onJoinShop();
    }

}
