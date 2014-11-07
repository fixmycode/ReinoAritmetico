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

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.lobby_layout, container, false);
        Player player = (Player) getArguments().getSerializable("player");
        setupPlayer(layout, player);
        return layout;
    }

    public void setupPlayer(View layout, Player player) {
        ImageView characterImage = (ImageView) layout.findViewById(R.id.character_image);

        TypedArray drawableArray = getResources().obtainTypedArray(R.array.character_list);
        int characterId = drawableArray.getResourceId(player.characterType, -1);
        characterImage.setImageResource(characterId);
        Button joinButton = (Button) layout.findViewById(R.id.join_button);
        joinButton.setOnClickListener(this);

        TextView nameText = (TextView) layout.findViewById(R.id.nameText);
        nameText.setText(player.name);

        TextView creditsText = (TextView) layout.findViewById(R.id.creditsText);
        creditsText.setText(getString(R.string.credits, player.credits));

        TextView characterTypeText = (TextView) layout.findViewById(R.id.characterTypeText);
        int characterType = 0;
        if(player.characterType==0){
            characterType = R.string.warrior;
        }
        else if(player.characterType==1){
            characterType = R.string.wizard;
        }
        else if(player.characterType == 2){
            characterType = R.string.archer;
        }
        characterTypeText.setText(characterType);
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

    @Override
    public void onClick(View v) {
        GameDialogFragment dialog = new GameDialogFragment();
        dialog.show(getFragmentManager(), "GAMEDIALOG");
    }

}
