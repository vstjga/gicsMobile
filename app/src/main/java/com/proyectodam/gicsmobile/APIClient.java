package com.proyectodam.gicsmobile;

import android.util.Log;
import android.widget.ArrayAdapter;


import com.proyectodam.gicsmobile.json.ListProductos;
import com.proyectodam.gicsmobile.json.Producto;

import retrofit.Call;
import retrofit.Callback;
import retrofit.GsonConverterFactory;
import retrofit.Response;
import retrofit.Retrofit;
import retrofit.http.GET;
import retrofit.http.Query;

import com.squareup.okhttp.ResponseBody;



public class APIClient {


  final String BASE_URL = "http://localhost/gics/";

 //   final String BASE_URL = "http://gics.260mb.net";
    final String API_KEY = "6969";

    Retrofit retrofit = new Retrofit.Builder()
            .baseUrl(BASE_URL)
            .addConverterFactory(GsonConverterFactory.create())
            .build();

    ApiGicsInterfase servei = retrofit.create(ApiGicsInterfase.class);

    public APIClient() {
        super();
    }


    public void getPorPrecio(final ArrayAdapter<Producto> adapter, String pais) {

        Call<ListProductos> call = servei.getPorPrecio(API_KEY);

        call.enqueue(new Callback<ListProductos>() {

                         @Override
                         public void onResponse(Response<ListProductos> response, Retrofit retrofit) {

                             if (response.isSuccess()) {

                                 ListProductos MisProductos = response.body();

                                 adapter.clear();
                                 for (Producto prod : MisProductos.getProductos()) {
                                     adapter.add(prod);

                                 }
                             } else {
                                 Log.e("ERROR_RESPONSE_VAL", response.errorBody().toString());
                                 adapter.clear();

                             }
                         }

                         @Override
                         public void onFailure(Throwable t) {
                             Log.d("D", "DEBUGJGA4");

                         }
                     }
        );
    }

    public void getPorNombre(final ArrayAdapter<Producto> adapter, String pais) {
        Call<ListProductos> call = servei.getPorNombre(API_KEY);
        call.enqueue(new Callback<ListProductos>() {
                         @Override
                         public void onResponse(Response<ListProductos> response, Retrofit retrofit) {
                             if (response.isSuccess()) {
                                 ListProductos MisProductos = response.body();

                                 adapter.clear();
                                 for (Producto prod : MisProductos.getProductos()) {
                                     adapter.add(prod);

                                 }
                             } else {

                                 adapter.clear();
                             }
                         }
                         @Override
                         public void onFailure(Throwable t) {
                         }
                     }
        );
    }

    interface ApiGicsInterfase {
       @GET("/productos/GetProductosPorPrecio")
       Call<ListProductos> getPorPrecio(@Query("apikey") String apiKey);

       @GET("/gics/productos/GetProductosPorNombre")
       Call<ListProductos> getPorNombre(@Query("apikey") String apiKey);
    }
}



// http://gics.260mb.net/gics/productos/GetProductosPorNombre?apikey=6969

// http://localhost/gics/productos/GetProductosPorPrecio?apikey=6969