import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Session } from 'src/app/core/models/session';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-edit-user',
  templateUrl: './edit-user.component.html',
  styleUrls: ['./edit-user.component.css'],
  providers: [UserService]

})
export class EditUserComponent implements OnInit {

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

  editSimple() {
    this._userService.editSimple(this.name,
      this.surname1,
      this.surname2,
      this.email,
      this.aboutMe,
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
          this._router.navigate(['/profile']);
        }, (error) => {
          console.log(error);
        }
      )

  }

}
