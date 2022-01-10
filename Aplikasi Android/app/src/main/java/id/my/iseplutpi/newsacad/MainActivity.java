package id.my.iseplutpi.newsacad;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SearchView;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

import java.util.List;

import id.my.iseplutpi.newsacad.model.NewsApiResponse;
import id.my.iseplutpi.newsacad.model.News;

public class MainActivity extends AppCompatActivity implements SelectListener, View.OnClickListener {
    RecyclerView recyclerView;
    CustomAdapter adapter;

    Button btn_1, btn_2, btn_3, btn_4, btn_5;
    private static String lastCategory = "";

    private static String lastQuery = "";

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        MenuInflater menuInflater = getMenuInflater();
        menuInflater.inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(@NonNull MenuItem item) {
        switch (item.getItemId()){
            case R.id.menu_help:
                Intent myIntent = new Intent(this, AboutActivity.class);
                startActivity(myIntent);
                break;

        }
        return super.onOptionsItemSelected(item);
    }

    SearchView searchView;

    SwipeRefreshLayout swipeRefreshLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // btutton category
        btn_1 = findViewById(R.id.btn_1);
        btn_1.setOnClickListener(this);
        btn_2 = findViewById(R.id.btn_2);
        btn_2.setOnClickListener(this);
        btn_3 = findViewById(R.id.btn_3);
        btn_3.setOnClickListener(this);
        btn_4 = findViewById(R.id.btn_4);
        btn_4.setOnClickListener(this);
        btn_5 = findViewById(R.id.btn_5);
        btn_5.setOnClickListener(this);

        // search
        searchView = findViewById(R.id.search_view);
        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
            @Override
            public boolean onQueryTextSubmit(String query) {
                Toast.makeText(MainActivity.this, "Fetching news article of " + query, Toast.LENGTH_SHORT).show();
                swipeRefreshLayout.setRefreshing(true);
                RequestManager manager = new RequestManager(MainActivity.this);
                manager.getNews(listener, "", query);
                setLastQuery(query);
                return true;
            }

            @Override
            public boolean onQueryTextChange(String newText) {
                return false;
            }
        });

        // refresh
        swipeRefreshLayout = findViewById(R.id.news_refresh);
        swipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                RequestManager manager = new RequestManager(MainActivity.this);
                manager.getNews(listener, MainActivity.getLastCategory(), MainActivity.getLastQuery());
            }
        });
        swipeRefreshLayout.setRefreshing(true);
        Toast.makeText(MainActivity.this, "Fetching News Article...", Toast.LENGTH_SHORT).show();

        // request api
        RequestManager manager = new RequestManager(this);
        manager.getNews(listener, getLastCategory(), getLastQuery());
    }

    private final OnFetchDataListener<NewsApiResponse> listener = new OnFetchDataListener<NewsApiResponse>() {
        @Override
        public void onFetchData(List<News> list, String message) {
            if (list.isEmpty()) {
                Toast.makeText(MainActivity.this, "No Data Found !!", Toast.LENGTH_SHORT).show();
            } else {
                showNews(list);
            }
            swipeRefreshLayout.setRefreshing(false);
        }

        @Override
        public void onError(String message) {
            Toast.makeText(MainActivity.this, "An Error Occured !!", Toast.LENGTH_SHORT).show();
        }
    };

    private void showNews(List<News> list) {
        recyclerView = findViewById(R.id.recycle_main);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new GridLayoutManager(this, 1));
        adapter = new CustomAdapter(this, list, this);
        recyclerView.setAdapter(adapter);
    }

    @Override
    public void OnNewsClick(News news) {
        startActivity(new Intent(MainActivity.this, DetailsActivity.class)
                .putExtra("data", news));
    }

    @Override
    public void onClick(View v) {
        Button button = (Button) v;
        String category = button.getText().toString();
        swipeRefreshLayout.setRefreshing(true);
        RequestManager manager = new RequestManager(this);
        manager.getNews(listener, category, null);
        setLastCategory(category);
    }

    public static String getLastCategory() {
        return lastCategory;
    }

    public static void setLastCategory(String lastCategory) {
        MainActivity.lastCategory = lastCategory;
    }

    public static String getLastQuery() {
        return lastQuery;
    }

    public static void setLastQuery(String lastQuery) {
        MainActivity.lastQuery = lastQuery;
    }
}