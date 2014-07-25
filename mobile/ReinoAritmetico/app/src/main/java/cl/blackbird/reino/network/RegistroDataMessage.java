package cl.blackbird.reino.network;

public class RegistroDataMessage {
    private String route,id, nombre, colegio, curso;

    public RegistroDataMessage(String route,String androidId, String nombre, String curso, String colegio){
        this.nombre=nombre;
        this.id= androidId;
        this.colegio=colegio;
        this.curso= curso;
        this.route = route;
    }

    public String getRoute(){
        return route;
    }
    public String getId(){return id;}
    public String getNombre(){
        return nombre;
    }
    public String getColegio(){
        return colegio;
    }
    public String getCurso(){
        return curso;
    }
}