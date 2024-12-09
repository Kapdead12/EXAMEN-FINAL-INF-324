from flask import Flask, render_template, request, redirect, url_for, flash
from flask_sqlalchemy import SQLAlchemy
import os
from sqlalchemy.exc import IntegrityError

#Conexion con la base datos en MySQL
app = Flask(__name__)
app.config.from_object('conexionBD.Config')
db = SQLAlchemy(app)
app.config['SECRET_KEY'] = os.urandom(24)

#================MODELOS O TABLAS DE LA BASE DE DATOS=================
class Freelancer(db.Model):
    __tablename__ = 'freelancers'
    id = db.Column(db.Integer, primary_key=True)
    ci = db.Column(db.String(50), unique=True)
    nombre = db.Column(db.String(255))
    contacto = db.Column(db.String(255))
    especialidad_id = db.Column(db.Integer, db.ForeignKey('especialidades.id'))
    especialidad = db.relationship('Especialidad', backref=db.backref('freelancers', lazy=True))

class Especialidad(db.Model):
    __tablename__ = 'especialidades'
    id = db.Column(db.Integer, primary_key=True)
    nombre = db.Column(db.String(255))

#================PARA LEER O LISTAR=====================
@app.route('/')
def listado():
    freelancers = Freelancer.query.all()
    especialidades = Especialidad.query.all()
    return render_template('vistaBDdatos.html', freelancers=freelancers, especialidades=especialidades)

#================PARA EDITAR=====================
@app.route('/editar/<int:ci>', methods=['GET'])
def editar(ci):
    freelancer = Freelancer.query.filter_by(ci=ci).first() 
    if not freelancer:
        flash('Freelancer no encontrado', 'error')
        return redirect(url_for('listado'))  # Redirige a la lista si no se encuentra
    
    especialidades = Especialidad.query.all() 
    return render_template('vistaModificar.html', freelancer=freelancer, especialidades=especialidades)


@app.route('/modificar', methods=['POST'])
def modificar():
    # Obtener los datos del formulario
    ci = request.form['ci']
    nombre = request.form['nombre']
    especialidad_id = request.form['especialidad_id']
    contacto = request.form['contacto']
    id = request.form['id']
    
    # Validar el formulario
    freelancer = Freelancer.query.get(id)
    if not freelancer:
        flash('Freelancer no encontrado', 'error')
        return redirect(url_for('listado'))  
    
    # Actualizar el freelancer
    freelancer.ci = ci
    freelancer.nombre = nombre
    freelancer.contacto = contacto
    freelancer.especialidad_id = especialidad_id
    
    # Guardar cambios en la base de datos
    db.session.commit()
    flash('Freelancer actualizado con éxito', 'success')
    return redirect(url_for('listado'))
    

#==============PARA ELIMINAR=====================
@app.route('/eliminar/<string:ci>', methods=['POST'])
def eliminar(ci):
    freelancer = Freelancer.query.filter_by(ci=ci).first()
    if freelancer:
        db.session.delete(freelancer)  
        db.session.commit()
        flash(f'Freelancer con CI {ci} eliminado con éxito.', 'success')
    else:
        flash('No se encontró al freelancer o ocurrió un error al eliminar.', 'error')

    return redirect(url_for('listado'))

#==============PARA ADICIONAR UN FREELANCER=====================
@app.route('/adicionar', methods=['GET'])
def adicionar():
    especialidades = Especialidad.query.all()  # Obtener todas las especialidades
    return render_template('vistaAdicionar.html', especialidades=especialidades)


@app.route('/agregar', methods=['POST'])
def agregar():
    ci = request.form['ci']
    nombre = request.form['nombre']
    especialidad_id = request.form['especialidad_id']
    contacto = request.form['contacto']

    # Validar datos
    if not ci or not nombre or not especialidad_id or not contacto:
        flash('Todos los campos son requeridos.', 'error')
        return redirect(url_for('adicionar'))
    
    freelancer = Freelancer(ci=ci, nombre=nombre, especialidad_id=especialidad_id, contacto=contacto)
    db.session.add(freelancer)
    db.session.commit()

    flash('Freelancer agregado con éxito', 'success')
    return redirect(url_for('listado'))

#==================MAIN========================
if __name__ == "__main__":
    app.run(debug=True, port=8000)

    