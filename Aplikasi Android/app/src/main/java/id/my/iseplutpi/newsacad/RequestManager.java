package id.my.iseplutpi.newsacad;

import android.content.Context;
import android.widget.Toast;

import id.my.iseplutpi.newsacad.model.NewsApiResponse;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.GET;
import retrofit2.http.Query;

public class RequestManager {
    Context context;
    Retrofit retrofit = new Retrofit.Builder()
            .baseUrl("https://news-acad.iseplutpi.my.id/api/news/")
            .addConverterFactory(GsonConverterFactory.create())
            .build();

    public void getNews(OnFetchDataListener listener, String category, String query) {
        CallNewsApi callNewsApi = retrofit.create(CallNewsApi.class);
        Call<NewsApiResponse> call = callNewsApi.callHeadlines("id", category, query, context.getString(R.string.api_key));

        try {
            call.enqueue(new Callback<NewsApiResponse>() {
                @Override
                public void onResponse(Call<NewsApiResponse> call, Response<NewsApiResponse> response) {
                    if (!response.isSuccessful()) {
                        Toast.makeText(context, "Error!!", Toast.LENGTH_SHORT).show();
                    }

                    listener.onFetchData(response.body().getArticles(), response.message());

                }

                @Override
                public void onFailure(Call<NewsApiResponse> call, Throwable t) {
                    listener.onError("Request Failed");
                }
            });
        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    public RequestManager(Context context) {
        this.context = context;
    }

    public interface CallNewsApi {
        @GET("acad")
        Call<NewsApiResponse> callHeadlines(
                @Query("country") String country,
                @Query("category") String category,
                @Query("q") String q,
                @Query("apiKey") String api_key
        );
    }
}
