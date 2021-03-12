import { Component, OnInit } from '@angular/core';
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
    private _sessionService: SessionService
  ) { }


  name:string;
  surname1:string;
  surname2:string;
  email:string;
  username:string;
  password:string;
  img:string;

  ngOnInit(): void {
    let user = this._sessionService.getCurrentUser();
    let token = this._sessionService.getCurrentToken();
    this.name = user.name;
    this.surname1 = user.surname1;
    this.surname2 = user.surname2;
    this.email = user.email;
    this.username = user.username;
    this.password = user.password;
    this.img = user.img;
  }

  /*
  usernameForm:string = "";
  passwordForm:string = "";
  img:string = "";
  *
/* TODO para un about me  */

  editUser() {

  }

}
