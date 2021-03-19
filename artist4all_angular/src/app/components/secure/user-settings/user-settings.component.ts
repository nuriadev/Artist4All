import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
@Component({
  selector: 'app-user-settings',
  templateUrl: './user-settings.component.html',
  styleUrls: ['./user-settings.component.css'],
  providers: [UserService]
})
export class UserSettingsComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _router: Router
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  name:string;
  surname1:string;
  name_2:string;
  surname1_2:string;
  surname2:string;
  email:string;
  username:string;
  password:string;
  img:FileList;
  aboutMe:string;

  ngOnInit(): void {
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.name_2 = this.user.name;
    this.surname1_2 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.img = this.user.img;
    this.aboutMe = this.user.aboutMe;
  }

  imgToUpload: FileList = null;
  handleFileInput(files: FileList) {
    this.imgToUpload = files;
  }

  // todo: poder modificar sin modificar la foto
  // todo añadir notificacion de cambio correcto
  // no se envia si no se escoge una img
  edit() {
    this._userService.edit(
      this.username,
      this.aboutMe,
      this.imgToUpload,
      this.name,
      this.email,
      this.surname1,
      this.surname2,
      this.token).subscribe(
        (result) => {
          let user = new User(
            result['name'],
            result['surname1'],
            result['surname2'],
            result['email'],
            result['username'],
            result['password'],
            result['type_user'],
            result['n_followers'],
            result['img'],
            result['aboutMe']
          );
          let userSession = new Session(result['token'], user);
          this._sessionService.setCurrentSession(userSession);
          // todo recargar pagina actual
          location.reload();
          //this._router.navigate(['/home']);
        }, (error) => {
          console.log(error);
        }
      )
  }

  // TODO: faltan los estilos de los tag 'a' al cambiar de sección
  changeSection(name_section:string) {
    let formProfile = document.getElementById("formProfile");
    let formAccount = document.getElementById("formAccount");
    let formPassword = document.getElementById("formPassword");
    let profileSection = document.getElementById("profile");
    let accountSection = document.getElementById("account");
    let passwordSection = document.getElementById("password");
    // let notificationSection = document.getElementById("notifications");
      switch(name_section) {
        case 'profile':
          formProfile.style.display = "inline-block";
          formAccount.style.display = "none";
          formPassword.style.display = "none";

          break;
        case 'account':
          formAccount.style.display = "inline-block";
          formProfile.style.display = "none";
          formPassword.style.display = "none";

          break;
        case 'password':
          formPassword.style.display = "inline-block";
          formProfile.style.display = "none";
          formAccount.style.display = "none";

          break;
      }
  }

  newPassword:string = "";
  confirmPassword:string = "";
  // todo verificar contraseñas iguales
  // todo añadir notificacion de cambio correcto
  editPassword() {
    this._userService.editPassword(this.newPassword, this.token).subscribe(
      (result) => {
        let user = new User(
          result['name'],
          result['surname1'],
          result['surname2'],
          result['email'],
          result['username'],
          result['password'],
          result['type_user'],
          result['n_followers'],
          result['img'],
          result['aboutMe']
        );
        let userSession = new Session(result['token'], user);
        this._sessionService.setCurrentSession(userSession);
          // todo recargar pagina actual

        this._router.navigate(['/settings']);
      }, (error) => {
        console.log(error);
      }
    )
  }
}

