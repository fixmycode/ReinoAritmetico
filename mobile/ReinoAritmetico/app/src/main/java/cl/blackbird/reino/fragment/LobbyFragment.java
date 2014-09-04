package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.content.res.TypedArray;
import android.graphics.drawable.AnimationDrawable;
import android.os.Bundle;
import android.app.Fragment;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import org.json.JSONArray;

import cl.blackbird.reino.R;
import cl.blackbird.reino.model.Player;

public class LobbyFragment extends Fragment implements CompoundButton.OnCheckedChangeListener {
    public static final String TAG = "RAFLOBBY";
    private LobbyListener mListener;
    private EditText lobbyCode;
    private ImageView warriorView;
    private AnimationDrawable warriorAnim;
    private CompoundButton joinButton;
    private TypedArray imgs;

    public static LobbyFragment newInstance(Player player) {
        LobbyFragment fragment = new LobbyFragment();
        Bundle args = new Bundle();
        args.putInt("characterType", player.characterType);
        fragment.setArguments(args);
        return fragment;
    }
    public LobbyFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.lobby_layout, container, false);

        imgs = getResources().obtainTypedArray(R.array.character_list);
        warriorView.setImageResource(imgs.getResourceId(
                getArguments().getInt("characterType"), -1));
        lobbyCode = (EditText) layout.findViewById(R.id.lobby_code);
        joinButton = (CompoundButton) layout.findViewById(R.id.join_button);
        joinButton.setOnCheckedChangeListener(this);
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

    /**
     * Checks if the field has any text and then enable the button
     * @param text the form field
     * @param button the button
     */
    private void checkValidForm(Editable text, CompoundButton button) {
        button.setEnabled(!text.toString().isEmpty());
    }

    /**
     * Every time that the activity gets on the screen, start the animation
     */
    @Override
    public void onResume() {
        super.onResume();
        /*if(warriorAnim == null){
            warriorView.setBackgroundResource(R.drawable.warrior_animation);
            warriorAnim = (AnimationDrawable) warriorView.getBackground();
        }

        warriorAnim.start();*/
    }

    /**
     * When the activity is not visible, we stop the animation. I don't really know if it's
     * necessary, but I'll add it anyway.
     */
    @Override
    public void onPause() {
        super.onPause();
        //warriorAnim.stop();
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

    /**
     * What happens when you click the Join/Leave button
     * @param buttonView the button
     * @param isChecked the value that changes
     */
    @Override
    public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
        if (mListener != null) {
            View layout = getView();
            if(layout != null) {

                if (lobbyCode != null) {
                    if(isChecked){
                        String code = lobbyCode.getText().toString();
                        lobbyCode.clearFocus();
                        mListener.onJoinServer(code);
                    } else {
                        mListener.onLeaveServer();
                        lobbyCode.requestFocus();
                    }
                    lobbyCode.setEnabled(!isChecked);
                    lobbyCode.setFocusable(!isChecked);
                    lobbyCode.setFocusableInTouchMode(!isChecked);
                    lobbyCode.setClickable(!isChecked);
                }
            }
        }
    }

    /**
     * Resets the UI to disconnected state
     */
    public void forceLeave() {
        joinButton.setChecked(false);
    }

    /**
     * Interface for the activity to communicate with the fragment
     * Read about the Listener pattern!
     */
    public interface LobbyListener {
        public void onJoinServer(String code);
        public void onLeaveServer();
    }

}
