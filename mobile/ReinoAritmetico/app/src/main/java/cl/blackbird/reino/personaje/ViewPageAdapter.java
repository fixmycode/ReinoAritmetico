package cl.blackbird.reino.personaje;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentStatePagerAdapter;

import cl.blackbird.reino.R;

/**
 * Created by niko on 31/08/2014.
 */
public class ViewPageAdapter  extends FragmentStatePagerAdapter {
    int[] image= {R.drawable.warrior_01, R.drawable.warrior_05,R.drawable.warrior_08};

    public ViewPageAdapter(FragmentManager fm){
        super(fm);
    }
    @Override
    public Fragment getItem(int position) {
        return ViewPagerFragment.newInstance(position,image[position]);
    }

    @Override
    public int getCount() {
        return image.length;
    }
}
