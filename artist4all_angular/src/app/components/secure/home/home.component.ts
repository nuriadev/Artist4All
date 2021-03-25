import { Component, OnInit } from '@angular/core';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers: [UserService]
})
export class HomeComponent implements OnInit {

  //public user: User;
  constructor(
    private _sessionService: SessionService
  ) { }

  ngOnInit(): void {
    //this.user = this._sessionService.getCurrentUser();
  }

}
