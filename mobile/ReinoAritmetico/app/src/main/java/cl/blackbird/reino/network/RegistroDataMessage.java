package cl.blackbird.reino.network;

public class RegistroDataMessage {
    private String route, content, nombre, colegio, curso;

    public RegistroDataMessage(String route, String content, String nombre, String colegio, String curso){
        this.nombre=nombre;
        this.colegio=colegio;
        this.curso= curso;
        this.route = route;
        this.content = content;
    }

    public String getRoute(){
        return route;
    }

    public String getContent(){
        return content;
    }
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