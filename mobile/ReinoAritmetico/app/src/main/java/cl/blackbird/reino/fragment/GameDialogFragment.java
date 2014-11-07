package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.EditText;

import cl.blackbird.reino.R;

/**
 * Created by Pablo Albornoz on 11/6/14.
 */
public class GameDialogFragment extends DialogFragment {
    private GameDialogListener listener;

    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {
        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
        LayoutInflater inflater = getActivity().getLayoutInflater();
        final View layout = inflater.inflate(R.layout.game_dialog, null);
        builder.setView(layout)
                .setPositiveButton(R.string.join_game, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        EditText lobbyCode = (EditText) layout.findViewById(R.id.lobby_code);
                        String code = lobbyCode.getText().toString();
                        if (listener != null && !code.equals("")) {
                            listener.onJoinServer(code);
                        }
                    }
                })
                .setNegativeButton(R.string.cancel, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        GameDialogFragment.this.getDialog().dismiss();
                    }
                });
        return builder.create();
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            listener = (GameDialogListener) activity;
        } catch (ClassCastException e){
            throw new ClassCastException(activity.toString() + " must implement GameDialogListener");
        }
    }

    public interface GameDialogListener {
        public void onJoinServer(String code);
    }
}
