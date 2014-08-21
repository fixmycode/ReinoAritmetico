package cl.blackbird.reino.fragment;

import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.content.Context;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import cl.blackbird.reino.R;
import cl.blackbird.reino.activity.MainActivity;

/**
* Created by Pablo Albornoz on 8/16/14.
*/
public class LoadingFragment extends Fragment {
    public static final String TAG = "RAFLOADING";

    public static LoadingFragment newInstance(int messageId){
        LoadingFragment loadingFragment = new LoadingFragment();
        Bundle args = new Bundle();
        args.putInt("message", messageId);
        loadingFragment.setArguments(args);
        return loadingFragment;
    }

    /**
     * Calls the loading screen with the following message
     * @param activity the activity that's attached
     * @param stringRes the resource id of the message
     */
    public static void setLoadingMessage(Activity activity, int stringRes) {
        FragmentManager manager = activity.getFragmentManager();
        LoadingFragment fragment = (LoadingFragment) manager.findFragmentByTag(TAG);
        if (fragment != null){
            Log.d(TAG, "Fragment recycled");
            fragment.setMessage(stringRes);
        } else {
            Log.d(TAG, "New fragment replaced");
            manager.beginTransaction()
                .setTransition(FragmentTransaction.TRANSIT_FRAGMENT_FADE)
                .replace(R.id.container, newInstance(stringRes), TAG)
                .commit();
        }
    }

    /**
     * Changes the message during the existence of the fragment
     * @param stringRes the resource id of the message
     */
    private void setMessage(int stringRes) {
        if(isActive()){
            View layout = this.getView();
            if(layout != null){
                TextView loadingMessage = (TextView) layout.findViewById(R.id.loading_message);
                loadingMessage.setText(stringRes);
            }
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.loading_layout, container, false);
        TextView loadingMessage = (TextView) layout.findViewById(R.id.loading_message);
        if(getArguments() != null){
            loadingMessage.setText(getArguments().getInt("message"));
        }
        return layout;
    }

    /**
     * If the fragment is being used
     * @return true if the fragment is active on screen
     */
    public boolean isActive() {
        return isAdded() && !isDetached() && !isRemoving();
    }
}
