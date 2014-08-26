package cl.blackbird.reino.fragment;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.ImageView;

import cl.blackbird.reino.activity.RegisterActivity;
import cl.blackbird.reino.R;

public class MyFragment extends Fragment {

    public static Fragment newInstance(RegisterActivity context, int pos,
                                       float scale)
    {
        Bundle b = new Bundle();
        b.putInt("pos", pos);
        b.putFloat("scale", scale);
        return Fragment.instantiate(context, MyFragment.class.getName(), b);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        if (container == null) {
            return null;
        }

        LinearLayout l = (LinearLayout)
                inflater.inflate(R.layout.mf, container, false);

        int pos = this.getArguments().getInt("pos");
        TextView tv = (TextView) l.findViewById(R.id.text);
        ImageView iv = (ImageView) l.findViewById(R.id.imageView);

        switch (pos){
            case 0:
                tv.setText("Mago");
                iv.setImageDrawable(getResources().getDrawable(R.drawable.ro_mage_male));
                break;
            case 1:
                tv.setText("Maga");
                iv.setImageDrawable(getResources().getDrawable(R.drawable.ro_mage_female));
                break;
            case 2:
                tv.setText("Arquero");
                iv.setImageDrawable(getResources().getDrawable(R.drawable.ro_archer_male));
                break;
            case 3:
                tv.setText("Arquera");
                iv.setImageDrawable(getResources().getDrawable(R.drawable.ro_archer_female));
                break;
        }

        MyLinearLayout root = (MyLinearLayout) l.findViewById(R.id.root);
        float scale = this.getArguments().getFloat("scale");
        root.setScaleBoth(scale);

        return l;
    }
}