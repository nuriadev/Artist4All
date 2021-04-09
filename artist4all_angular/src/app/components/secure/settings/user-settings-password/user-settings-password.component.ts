import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-user-settings-password',
  templateUrl: './user-settings-password.component.html',
  styleUrls: ['./user-settings-password.component.css']
})
export class UserSettingsPasswordComponent implements OnInit {

  constructor(
    private _sessionService: SessionService,
    private _userService: UserService,
    private _router: Router
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  id:number;

  ngOnInit(): void {
    this.id = this.user.id;
  }

  newPassword:string = "";
  confirmPassword:string = "";
  userEdited:User;
  // todo verificar contraseñas iguales
  // todo añadir notificacion de cambio correcto
  editPassword() {
    this._userService.editPassword(this.id, this.newPassword, this.token).subscribe(
      (result) => {
        this.userEdited = result.user;
        let userSession = new Session(result.token, this.userEdited);
        this._sessionService.setCurrentSession(userSession);
      }, (error) => {
        console.log(error);
      }
    )
  }
}

