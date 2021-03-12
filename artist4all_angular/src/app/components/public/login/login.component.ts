import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { LoginUser } from 'src/app/core/models/loginUser';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { SessionService } from 'src/app/core/services/session.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [AuthenticationService, SessionService]
})
export class LoginComponent implements OnInit {

  constructor(
    private _authenticationService: AuthenticationService,
    private _sessionService: SessionService,
    private _router: Router
  ) { }

  ngOnInit(): void {}

  email:string = "";
  password:string = "";
  login() {
    this._authenticationService.login(new LoginUser(this.email, this.password)).subscribe(
      (result) => {
        if (result["token"] != null) {
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
