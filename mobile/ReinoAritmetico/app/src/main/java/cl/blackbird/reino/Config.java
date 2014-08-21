package cl.blackbird.reino;

import android.content.Context;
import android.preference.PreferenceManager;

public abstract class Config {
    public static String getServer(Context context){
        return PreferenceManager.getDefaultSharedPreferences(context)
                .getString("server_host", context.getString(R.string.default_server));
    }
}
