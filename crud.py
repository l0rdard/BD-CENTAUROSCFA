import mysql.connector

with open("credenciales.txt", "r", encoding="utf-8") as archivo:
    host, usuario, contraseña, base = archivo.read().splitlines()

conexion = mysql.connector.connect(
    host=host,
    user=usuario,
    password=contraseña,
    database=base,
    port=3306
)

cursor = conexion.cursor(dictionary=True)


def crear_jugador(dni, nombre, apellido):
    cursor.execute(
        "INSERT INTO jugador (dni, nombre, apellido) VALUES (%s, %s, %s)",
        (dni, nombre, apellido)
    )
    conexion.commit()
    print("Jugador creado correctamente.")


def leer_jugadores():
    cursor.execute("SELECT * FROM jugador")
    jugadores = cursor.fetchall()

    if not jugadores:
        print("No hay jugadores registrados.")
        return

    for j in jugadores:
        print(j["id_jugador"], j["dni"], j["nombre"], j["apellido"])


def actualizar_jugador(id, dni, nombre, apellido):
    cursor.execute(
        "UPDATE jugador SET dni=%s, nombre=%s, apellido=%s WHERE id_jugador=%s",
        (dni, nombre, apellido, id)
    )
    conexion.commit()

    if cursor.rowcount > 0:
        print("Jugador actualizado.")
    else:
        print("No se encontró el jugador o los datos son iguales.")


def eliminar_jugador(id):
    cursor.execute("DELETE FROM jugador WHERE id_jugador=%s", (id,))
    conexion.commit()

    if cursor.rowcount > 0:
        print("Jugador eliminado correctamente.")
    else:
        print("No se encontró un jugador con ese ID.")


while True:
    print("\nCRUD EN PYTHON CON MYSQL")
    print("1. Crear jugador")
    print("2. Consultar jugadores")
    print("3. Actualizar jugador")
    print("4. Eliminar jugador")
    print("5. Salir")

    opcion = input("Elegí una opción: ")

    if opcion == "1":
        dni = input("DNI: ")
        nombre = input("Nombre: ")
        apellido = input("Apellido: ")
        crear_jugador(dni, nombre, apellido)

    elif opcion == "2":
        leer_jugadores()

    elif opcion == "3":
        id = input("ID del jugador a actualizar: ")
        dni = input("Nuevo DNI: ")
        nombre = input("Nuevo nombre: ")
        apellido = input("Nuevo apellido: ")
        actualizar_jugador(id, dni, nombre, apellido)

    elif opcion == "4":
        id = input("ID del jugador a eliminar: ")
        eliminar_jugador(id)

    elif opcion == "5":
        print("Saliendo del programa.")
        break

    else:
        print("Opción no válida.")

conexion.close()