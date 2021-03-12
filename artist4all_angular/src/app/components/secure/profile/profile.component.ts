import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from 'src/app/core/services/authentication.service';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css'],
  providers: [UserService]
})
export class ProfileComponent implements OnInit {

  constructor(
    private _sessionService: SessionService
  ) { }

  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();
  name:string;
  surname1:string;
  surname2:string;
  email:string;
  username:string;
  password:string;
  img:string;

  ngOnInit(): void {
    this.name = this.user.name;
    this.surname1 = this.user.surname1;
    this.surname2 = this.user.surname2;
    this.email = this.user.email;
    this.username = this.user.username;
    this.password = this.user.password;
    this.img = this.user.img;
  }

}
