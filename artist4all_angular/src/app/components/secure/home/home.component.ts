import { Component, OnInit } from '@angular/core';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css'],
  providers: [UserService]
})
export class HomeComponent implements OnInit {

  constructor() { }

  ngOnInit(): void {
    // setInterval(function() {
    //   if (window.localStorage) {
    //     if (window.localStorage.getItem('token') !== undefined && window.localStorage.getItem('token')) {

    //     }
    //   }
    // }, 500);
  }

}
