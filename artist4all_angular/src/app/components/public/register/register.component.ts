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

  user:User;

  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  password:string = "";
  passwordConfirm:string = "";
  isArtist:number;
  isPrivate = 0;

  // todo: comprobar que las contraseñas sean iguales
  register() {
    this._userService.register(new User(null, this.name, this.surname1, this.surname2, this.email, this.username, this.password, this.isArtist, null, '', this.isPrivate)).subscribe(
      (result) => {
        if (result['token'] != null) {
          this.user = result.user;
          let userSession = new Session(result.token, this.user);
          this._sessionService.setCurrentSession(userSession);
          this._router.navigate(['/home']);
        }
      }, (error) => {
        console.log(error);
      }
    )
  }

}
