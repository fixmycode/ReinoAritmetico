package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.graphics.drawable.AnimationDrawable;
import android.os.Bundle;
import android.app.Fragment;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import cl.blackbird.reino.R;

public class LobbyFragment extends Fragment implements View.OnClickListener {
    public static final String TAG = "RAFLOBBY";
    private LobbyListener mListener;
    private EditText lobbyCode;
    private ImageView warriorView;
    private AnimationDrawable warriorAnim;
    private Button joinButton;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.lobby_layout, container, false);

        warriorView = (ImageView) layout.findViewById(R.id.warrior_anim);
        lobbyCode = (EditText) layout.findViewById(R.id.lobby_code);
        joinButton = (Button) layout.findViewById(R.id.join_button);
        joinButton.setOnClickListener(this);
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
    private void checkValidForm(Editable text, Button button) {
        button.setEnabled(!text.toString().isEmpty());
    }

    /**
     * Every time that the activity gets on the screen, start the animation
     */
    @Override
    public void onResume() {
        super.onResume();
        if(warriorAnim == null){
            warriorView.setBackgroundResource(R.drawable.warrior_animation);
            warriorAnim = (AnimationDrawable) warriorView.getBackground();
        }

        warriorAnim.start();
    }

    /**
     * When the activity is not visible, we stop the animation. I don't really know if it's
     * necessary, but I'll add it anyway.
     */
    @Override
    public void onPause() {
        super.onPause();
        warriorAnim.stop();
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
    }

}
