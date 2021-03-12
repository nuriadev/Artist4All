import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { SessionService } from 'src/app/core/services/session.service';
import { User } from '../../../core/models/user';
import { UserService } from '../../../core/services/user.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers: [SessionService, UserService]
})

export class RegisterComponent implements OnInit {

  constructor(
    private _userService: UserService,
    private _sessionService: SessionService,
    private _router: Router
  ) { }

  ngOnInit(): void {
  }

  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  password:string = "";
  passwordConfirm:string = "";
  type_user:number;

  // todo: comprobar que las contraseñas sean iguales
  register() {
    this._userService.register(new User(this.name, this.surname1, this.surname2, this.email, this.username, this.password, this.type_user, 0, '')).subscribe(
      (result) => {
        if (result['token'] != null) {
          let user = new User(result['name'], result['surname1'], result['surname2'], result['email'], result['username'], result['password'], result['type_user'], result['n_followers'], result['img']);
          let userSession = new Session(result['token'], user);
          this._sessionService.setCurrentSession(userSession);
          this._router.navigate(['/home']);
        }
      }, (error) => {
        console.log(error);
      }
    )
  }

}
