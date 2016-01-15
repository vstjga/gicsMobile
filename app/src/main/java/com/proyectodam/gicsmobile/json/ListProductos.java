


package com.proyectodam.gicsmobile.json;

import java.util.ArrayList;
import java.util.List;
//       import javax.annotation.Generated;

// @Generated("org.jsonschema2pojo")
public class ListProductos {


    private List<Producto> Productos = new ArrayList<Producto>();

    /**
     * No args constructor for use in serialization
     *
     */
    public ListProductos() {
    }

    /**
     *
     * @param Productos
     */
    public ListProductos(List<Producto> Productos) {
        this.Productos = Productos;
    }


    /**
     *
     * @return
     *     The Productos
     */
    public List<Producto> getProductos() {
        return Productos;
    }

    /**
     *
     * @param Productos
     *     The Productos
     */
    public void setProductos(List<Producto> Productos) {
        this.Productos = Productos;
    }






}
